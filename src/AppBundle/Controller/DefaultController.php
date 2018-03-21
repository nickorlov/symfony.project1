<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function createAction(Request $request)
    {
        $product = new Product();

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('price', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Product'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return new Response('Product ' . $product->getName() . ' added!');
        }

        return $this->render(
            'default/index.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/show/{productId}")
     */
    public function showAction($productId)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $productId
            );
        }

        return new Response(
            '<html><body><p>' . $product->getName() . '</p><p>' . $product->getPrice() . '</p><p>' . $product->getDescription() . '</p></body></html>'
        );
    }

    /**
     * @Route("/update/{productId}")
     */
    public function updateAction($productId)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $productId
            );
        }

        $product->setName('New product!');
        $em->flush();

        return new Response('Updated product name with id ' . $product->getId());
    }
}
