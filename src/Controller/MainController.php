<?php

namespace App\Controller;

use App\Form\ContentFormType;
use App\Service\LinkGenerator;
use App\Service\SecretService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request, SecretService $secretService)
    {
        $form = $this->createForm(ContentFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            list($key, $token) = $secretService->save($form->get('content')->getData(), $form->get('duration')->getData());

            return $this->redirectToRoute('main_message_url', ['token' => $token, 'key' => $key]);
        }

        return $this->render('main/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/messageUrl/{token}/{key}", name="main_message_url")
     */
    public function messageUrl(string $token, string $key, LinkGenerator $linkGenerator): Response
    {
        return $this->render('main/file_url.html.twig', [
            'url' => $linkGenerator->genererate($token, $key)
        ]);
    }


    /**
     * @Route("/beforeShow/{token}/{key}", name="before_show")
     */
    public function beforeShow(string $token, string $key, SecretService $secretService): Response
    {
        $isValid = $secretService->checkValid($token, $key);

        if (!$isValid) {
            return $this->render('main/before_show_error.html.twig', [
                'token' => $token,
                'key' => $key
            ]);
        }

        return $this->render('main/before_show.html.twig', [
            'token' => $token,
            'key' => $key
        ]);
    }


    /**
     * @Route("/show/{token}/{key}", name="main_show")
     */
    public function show(string $token, string $key, SecretService $secretService): Response
    {
        $content = $secretService->getAndDelete($token, $key);

        return $this->render('main/show.html.twig', [
            'content' => $content
        ]);
    }

}
