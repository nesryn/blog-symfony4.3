<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;


/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}",name ="blog_list", defaults={"page":1} , requirements={"page"="\d+"})
     */
    public function list($page , BlogPostRepository $repository)
    {
        $blogs=$repository->findAll();
            return $this->json(
                [
                    'page'=> $page,
                    'data'=>array_map(function (BlogPost $blog)
                    {
                        return $this-> generateUrl('blog_by_id',['id'=> $blog->getId() ]);
                    }, $blogs)
                ]
            );
    }

    /**
     * @Route("/post/{id}",name="blog_by_id", requirements={"id"="\d+"},  methods={"GET"})
     */
    public function post(BlogPost $post){

       // dump(array_column(self::Posts,'title'));exit;
        return $this->json(
            //     rturn index       array_search($id,array_column(self::Posts,'id'))
           // self::Posts[array_search($id,array_column(self::Posts,'id'))]


        // it's the same as $repository->findOneBy(['id'=>$id])
            $post

        );
    }


    /**
     * @Route("/post/{slug}",name="blog_by_slug", methods={"GET"})
     */
    public function postBySlug($slug, BlogPostRepository $repository){
       // dump(array_column(self::Posts,'slug'));exit;
        return $this->json(  $repository->findOneBy(['slug'=>$slug])
        );
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }


    /**
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
     */
    public function delete(BlogPost $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
