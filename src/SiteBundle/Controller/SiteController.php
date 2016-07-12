<?php

namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Repository\BlockRepository;
use SiteBundle\Repository\TagRepository;
use SiteBundle\Manager\FileManager;
use SiteBundle\Manager\ArticleManager;
use SiteBundle\Manager\BlockManager;
use SiteBundle\Manager\ProductManager;
use SiteBundle\Services\ImageResize;
use SiteBundle\Entity\Article;
use SiteBundle\Entity\Tag;
use SiteBundle\Entity\Product;
use SiteBundle\Entity\Block;
use SiteBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

use Mremi\ContactBundle\ContactEvents;
use Mremi\ContactBundle\Event\ContactEvent;
use Mremi\ContactBundle\Event\FilterContactResponseEvent;
use Mremi\ContactBundle\Event\FormEvent;
use Mremi\ContactBundle\Form\Factory\FormFactory;
use Mremi\ContactBundle\Model\ContactManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SiteBundle\Repository\ContactsRepository;
use SiteBundle\Repository\ArticleRepository;

class SiteController extends Controller
{

    /**
     *
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        /** @var $blockRepository BlockRepository */
        $blockRepository = $this->getDoctrine()->getRepository("SiteBundle:Block");
        $topSliderBlocks = $blockRepository->getTopSliderBlocks($this->getParameter('top.slider.count'));
        $midGridBlocks = $blockRepository->getMidGridBlocks($this->getParameter('mid.grid.count'));
        $tabBlocks = $blockRepository->getTabBlocks($this->getParameter('index.tab.count'));
//        print_r(count($socialBlocks));
//        die();

        /** @var $blockManager BlockManager */
        $blockManager = $this->get('block.manager');
        /** @var $file_manager FileManager*/
        $file_manager = $this->get('file.manager');

        $blocksToSave = array();


        $blockManager->blocksImageResize($topSliderBlocks, array(
            '1280x500x2',
        ), $blocksToSave);

        $blockManager->blocksImageResize($midGridBlocks, array(
            '52x52x2',
        ), $blocksToSave);

        $blockManager->blocksImageResize($tabBlocks, array(
            '300x300x1',
        ), $blocksToSave);

        /** @var $block Block*/
        foreach ($blocksToSave as $block) {
            $file_manager->saveImageEntity($block->getImage());
        }



//        /** @var $article_repository ArticleRepository */
//        $article_repository = $this->getDoctrine()->getRepository("SiteBundle:Article");
//        /** @var $tag_repository TagRepository */
//        $tag_repository = $this->getDoctrine()->getRepository("SiteBundle:Tag");
//
//        /** @var $file_manager FileManager */
//        $file_manager = $this->get('file.manager');
//
//        //$rtype = ImageResize::CROP_IMAGE_ENTROPY;
//
//        $topSliderArticles = $article_repository->getTopBannerArticles();
//
//        $articlesToSave = array();
//
//        $article_manager->articlesImageResize($topBannerArticles, array(
//            '524x524x2',
//            '524x261x2',
//            '261x261x2',
//            '490x490x2',
//            '340x242x2',
//        ), $articlesToSave);
//
//
//        /** @var $article Article */
//
//        foreach ($articlesToSave as $article) {
//            $file_manager->saveImageEntity($article->getImage());
//        }
//
//
        return $this->render('SiteBundle:Site:index.html.twig', array(
            'topSliderBlocks' => $topSliderBlocks,
            'midGridBlocks' => $midGridBlocks,
            'tabBlocks' => $tabBlocks,
            'is_main' => false
//            'socialBlocks' => $socialBlocks
        ));
    }

    /**
     * @Route("/products/", name="products")
     * @Route("/products/category/{slug}/", name="products_cat", requirements={"slug" = "[a-z0-9-_]*"})
     */

    public function productsAction($slug = false) {

        $title = "Продукция";

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem($title, "products");
        $breadcrumbs->prependRouteItem("Главная", "index");

        $allCategories = $this->getDoctrine()->getRepository("SiteBundle:Category")->getAllMainCategories();
        
        /** @var $productManager ProductManager */
        $productManager = $this->get('product.manager');
        /** @var $file_manager FileManager*/
        $file_manager = $this->get('file.manager');

        if (!$slug) {
            $categories = $allCategories;
        } else {
            $categories = $this->getDoctrine()->getRepository("SiteBundle:Category")->getMainCategories($slug);
                /*->findBy(array('slug' => $slug));*/
        }



        $prodToSave = array();

        /** @var $category Category*/
        foreach ($allCategories as $category) {
            $productManager->productImageResize($category->getProducts(), array(
                '140x117x2',
            ), $prodToSave);
        }

        /** @var $product Product*/
        foreach ($prodToSave as $product) {
            $image = $product->getImage();
            if (is_a($image, 'SiteBundle\Entity\File')) {
                $file_manager->saveImageEntity($image);
            }
        }



        return $this->render('SiteBundle:Site:products.html.twig', array(
            'categories' => $categories,
            'allCategories' => $allCategories,
            'slug' => $slug,
            'title' => $title,
            'is_main' => true
        ));
    }

    /**
     * @Route("/news/", name="news")
     * @Route("/news/page/{page}/", name="news_page", requirements={"page" = "[0-9]*"})
     */

    public function newsAction($page = 1) {

        $title = 'Новости';

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem($title, "products");
        $breadcrumbs->prependRouteItem("Главная", "index");

        /** @var $articleRepository ArticleRepository */
        $articleRepository = $this->getDoctrine()->getRepository("SiteBundle:Article");
        $articlesQuery = $articleRepository->getArticles(true);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $articlesQuery, /* query NOT result */
            $page/*page number*/,
            $this->getParameter('news.page.count')/*limit per page*/
        );

        $paginationData = $pagination->getPaginationData();
        $paginationData['params'] = $pagination->getParams();

        $articlesToSave = array();

        /** @var $file_manager FileManager*/
        $file_manager = $this->get('file.manager');

        /** @var $article_manager ArticleManager*/
        $article_manager = $this->get('article.manager');

        $articles = $pagination->getItems();

        $article_manager->articlesImageResize($articles,array('1250x500x2'), $articlesToSave);

        foreach ($articlesToSave as $article) {
            $file_manager->saveImageEntity($article->getImage());
        }

        return $this->render('SiteBundle:Site:news.html.twig', array('pagination' => $paginationData, 'articles' => $articles, 'is_main' => true, 'title' => $title));
//        $title = "Продукция";
//
//        $breadcrumbs = $this->get("white_october_breadcrumbs");
//        $breadcrumbs->addRouteItem($title, "products");
//        $breadcrumbs->prependRouteItem("Главная", "index");
//
//
//        /** @var $articleRepository ArticleRepository */
//        $articleRepository = $this->getDoctrine()->getRepository("SiteBundle:Article");
//        /** @var $articleManager ArticleManager */
//        $articleManager = $this->get('article.manager');
//        /** @var $file_manager FileManager*/
//        $file_manager = $this->get('file.manager');
//
//        $articles = $articleRepository;
//
//        $prodToSave = array();
//
//        /** @var $category Category*/
//        foreach ($allCategories as $category) {
//            $productManager->productImageResize($category->getProducts(), array(
//                '140x117x2',
//            ), $prodToSave);
//        }
//
//        /** @var $product Product*/
//        foreach ($prodToSave as $product) {
//            $file_manager->saveImageEntity($product->getImage());
//        }
//
//
//
//        return $this->render('SiteBundle:Site:products.html.twig', array(
//            'categories' => $categories,
//            'allCategories' => $allCategories,
//            'slug' => $slug,
//            'title' => $title,
//            'is_main' => true
//        ));
    }


    /**
     * @Route("/product/{slug}/", name="product_detail", requirements={"slug" = "[a-z0-9-_]*"})
     */

    public function productAction($slug = false) {
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        /** @var $product Product*/
        $product = $this->getDoctrine()->getRepository("SiteBundle:Product")->findOneBy(array('slug' => $slug));
        $breadcrumbs->addRouteItem('Продукция', "products");
        $title = $product->getExtendedName();
        $breadcrumbs->addRouteItem($title, 'product_detail', array('slug' => $slug));
        $breadcrumbs->prependRouteItem("Главная", "index");

//        \IntlDateFormatter::MEDIUM

        return $this->render('SiteBundle:Site:product_detail.html.twig', array(
            'product' => $product,
            'title' => $title,
            'is_main' => true
        ));
    }

    /**
     * @Route("/news/{slug}/", name="news_detail", requirements={"slug" = "[a-z0-9-_]*"})
     */

    public function newsDetailAction($slug = false) {
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        /** @var $article Article*/
        $article = $this->getDoctrine()->getRepository("SiteBundle:Article")->findOneBy(array('slug' => $slug));
        $breadcrumbs->addRouteItem('Новости', "news");
        $title = $article->getExtendedName();
        $breadcrumbs->addRouteItem($title, 'news_detail', array('slug' => $slug));
        $breadcrumbs->prependRouteItem("Главная", "index");

        /** @var $file_manager FileManager*/
        $file_manager = $this->get('file.manager');
        $file_manager->resizeImage($article->getImage(), '823x463x2');

        return $this->render('SiteBundle:Site:article_detail.html.twig', array(
            'article' => $article,
            'title' => $title,
            'is_main' => true
        ));
    }


    /**
     * @Route("/article/{slug}/", name="article_detail", requirements={"slug" = "[a-z0-9-_]*"})
     */
    public function articleAction($slug) {
        /** @var $article_repository ArticleRepository */
        /** @var $article Article*/
        $article_repository = $this->getDoctrine()->getRepository("SiteBundle:Article");
        $article = $article_repository->findOneBy(array("slug" => $slug));
        /** @var $article_manager ArticleManager */
        $article_manager = $this->get('article.manager');
        return $article_manager->getArticleResponse($article, new Response());
    }

    /**
     * @Route("/test/", name="test")
     */

    public function testAction() {

        /** @var $blockRepository \Doctrine\ORM\EntityRepository*/
        $blockRepository = $this->getDoctrine()->getRepository("SiteBundle:Block");

        $qb = $blockRepository->createQueryBuilder('b');
        $qb->join('b.blockSort', 'bs')
            ->where($qb->expr()->eq('bs.code', '\'middle_blocks_main\''))
            ->andWhere($qb->expr()->isNotNull('bs.sort'))
            ->orderBy('bs.sort', 'desc');

        $result = $qb->getQuery()->getResult();

        return new Response(print_r($result[0]->getId(), true));
    }

    public function socialBlockAction() {

        $blockRepository = $this->getDoctrine()->getRepository("SiteBundle:Block");

        $socialBlocks = $blockRepository->getSocialBlocks($this->getParameter('socials.count'));

        return $this->render('SiteBundle::social.html.twig', array(
            'socialBlocks' => $socialBlocks,
        ));
    }

    public function headerContactsBlockAction() {
        $contacts = $this->getDoctrine()->getRepository("SiteBundle:Contacts")->getContactsIndexedByCode();


        return $this->render('SiteBundle::header_contacts.html.twig', array(
            'contacts' => $contacts,
        ));
    }

    public function mainCategoriesMenuAction() {

        $allCategories = $this->getDoctrine()->getRepository("SiteBundle:Category")->getAllMainCategories();

        return $this->render('SiteBundle::main_categories_menu.html.twig', array(
            'allCategories' => $allCategories
        ));
    }



    /**
     * @Route("/contacts/", name="contacts")
     * @Route("/contacts/confirm/", name="contacts_confirm")
     */

    public function feedbackFormAction() {

        $breadcrumbs = $this->get("white_october_breadcrumbs");

        $breadcrumbs->addRouteItem('Контакты', "contacts");
        $breadcrumbs->prependRouteItem("Главная", "index");

        $eventDispatcher = $this->get('event_dispatcher');
        $formFactory = $this->get('mremi_contact.form_factory');
        $contactManager = $this->get('mremi_contact.contact_manager');
        $router = $this->get('router');
        $session = $this->get('session');
        $request = $this->get('request');

        $routeName = $request->get('_route');

        if ($routeName == 'contacts_confirm') {
            $confirmMessage = $this->getParameter('feedback_form_confirm');
        } else {
            $confirmMessage = false;
        }

        $contact = $contactManager->create();

        $eventDispatcher->dispatch(ContactEvents::FORM_INITIALIZE, new ContactEvent($contact, $request));

        $form = $formFactory->createForm($contact);

        $form->handleRequest($request);

        /** @var $contactsRepository ContactsRepository*/
        $contactsRepository = $this->getDoctrine()->getRepository("SiteBundle:Contacts");
        $contacts = $contactsRepository->getContactsIndexedByCode();

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $eventDispatcher->dispatch(ContactEvents::FORM_SUCCESS, $event);

            //if (null === $response = $event->getResponse()) {
                $response = new RedirectResponse($router->generate('contacts_confirm'));
            //}

            $contactManager->save($contact, true);
            $session->set('mremi_contact_data', $contact);

            $eventDispatcher->dispatch(ContactEvents::FORM_COMPLETED, new FilterContactResponseEvent($contact, $request, $response));

            return $response;
        }



        return $this->render('SiteBundle::feedback.html.twig', array(
            'form' => $form->createView(),
            'form_action' => $this->generateUrl('contacts'),
            'is_main' => true,
            'title' => "Контакты",
            'contacts' => $contacts,
            'confirmMessage' => $confirmMessage
        ));
    }

}
