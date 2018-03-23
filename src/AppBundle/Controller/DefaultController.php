<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Car;
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
        $car = new Car();

        $form = $this->createFormBuilder($car)
            ->add('name', TextType::class)
            ->add('numbers', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create a car'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            return new Response('Car "'.$car->getName().'" successfully added!');
        }

        return $this->render(
            'default/index.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/show/{carId}")
     */
    public function showAction($carId)
    {
        $car = $this->getDoctrine()
            ->getRepository(Car::class)
            ->find($carId);

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id '.$carId
            );
        }

        return new Response(
            '<html><body><p>'.$car->getName().'</p><p>'.$car->getNumbers().'</p></body></html>'
        );
    }

    /**
     * @Route("/update/{carId}")
     */
    public function updateAction($carId)
    {
        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository(Car::class)->find($carId);

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id '.$carId
            );
        }

        $car->setName('New car2');
        $em->flush();

        return new Response('Updated car name with id '.$car->getId());
    }
}
