<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeFormType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Session\Session;

class EmployeeController extends AbstractController
{
    /**
     * @route("/", name="home")
     * Method({"GET", "POST"})
     */
    public function index(Request $request)
    {
        $session = new Session();
        $session->start();

        $employee = new Employee();

        $form = $this->createForm(EmployeeFormType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employee = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($employee);
            $entityManager->flush();

            $session->getFlashBag()->add('info', 'Added successfully!');

            return $this->redirectToRoute('home');
        }

        $employees = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->findAll();

        return $this->render('index.html.twig', [
            'form' => $form->createView(),
            'employees' => $employees,
        ]);

        // return $this->render('index.html.twig');
    }
}
