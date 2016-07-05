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
//            'socialBlocks' => $socialBlocks
        ));
    }

    /**
     * @Route("/products/", name="products")
     * @Route("/products/category/{slug}/", name="products_cat", requirements={"slug" = "[a-z-]*"})
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
            $file_manager->saveImageEntity($product->getImage());
        }



        return $this->render('SiteBundle:Site:products.html.twig', array(
            'categories' => $categories,
            'allCategories' => $allCategories,
            'slug' => $slug,
            'title' => $title
        ));
    }


    /**
     * @Route("/article/{slug}/", name="article_detail", requirements={"slug" = "[a-z-]*"})
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
            'socialBlocks' => $socialBlocks
        ));
    }


}
