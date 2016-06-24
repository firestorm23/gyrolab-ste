<?php

namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Repository\ArticleRepository;
use SiteBundle\Repository\TagRepository;
use SiteBundle\Manager\FileManager;
use SiteBundle\Manager\ArticleManager;
use SiteBundle\Services\ImageResize;
use SiteBundle\Entity\Article;
use SiteBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{

    /**
     *
     * @Route("/")
     */
    public function indexAction()
    {
        /** @var $article_repository ArticleRepository */
        $article_repository = $this->getDoctrine()->getRepository("SiteBundle:Article");
        /** @var $tag_repository TagRepository */
        $tag_repository = $this->getDoctrine()->getRepository("SiteBundle:Tag");
        
        /** @var $file_manager FileManager */
        $file_manager = $this->get('file.manager');

        //$rtype = ImageResize::CROP_IMAGE_ENTROPY;

        $topBannerArticles = $article_repository->getTopBannerArticles();
        $recommendedArticles = $article_repository->getRecommendedArticles();
        $topArticles = $article_repository->getTopArticles();
        $latestCommentedArticles =  $article_repository->getLatestCommentedArticles(4);
        $mostViewArticles = $article_repository->getMostViewArticles(4);

        /** @var $tagOfTheDay Tag*/
        $tag_repository->getTagOfTheDay();
        $tagOfTheDay = $tag_repository->getTagOfTheDay();
        $tagOfTheDay = current($tagOfTheDay);

        $tagOfTheDayArticles = $tagOfTheDay->getArticles();

        /** @var $article_manager ArticleManager*/
        $article_manager = $this->get('article.manager');

        $articlesToSave = array();

        $article_manager->articlesImageResize($topBannerArticles, array(
            '524x524x2',
            '524x261x2',
            '261x261x2',
            '490x490x2',
            '340x242x2',
        ), $articlesToSave);

        $article_manager->articlesImageResize($recommendedArticles, array(
            '340x242x2',
        ), $articlesToSave);

        $article_manager->articlesImageResize($tagOfTheDayArticles, array(
            '330x242x2',
            '100x100x2',
        ), $articlesToSave);

        $article_manager->articlesImageResize($mostViewArticles, array(
            '330x242x2',
        ), $articlesToSave);

        $article_manager->articlesImageResize($latestCommentedArticles, array(
            '100x100x2',
        ), $articlesToSave);



        /** @var $article Article */

        foreach ($articlesToSave as $article) {
            $file_manager->saveImageEntity($article->getImage());
        }


        return $this->render('SiteBundle:Site:index.html.twig', array(
            'topBannerArticles' => $topBannerArticles,
            'recommendedArticles' => $recommendedArticles,
            'topArticles' => $topArticles,
            'tagOfTheDay' => $tagOfTheDay,
            'mostViewArticles' => $mostViewArticles,
            'latestCommentedArticles' => $latestCommentedArticles
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

}
