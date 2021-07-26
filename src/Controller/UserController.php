<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\UserBundle\Controller;

use FlexPHP\Bundle\UserBundle\Domain\User\Request\CreateUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\DeleteUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\FindUserUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\IndexUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\ReadUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\UpdateUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\UseCase\CreateUserUseCase;
use FlexPHP\Bundle\UserBundle\Domain\User\UseCase\DeleteUserUseCase;
use FlexPHP\Bundle\UserBundle\Domain\User\UseCase\FindUserUserStatusUseCase;
use FlexPHP\Bundle\UserBundle\Domain\User\UseCase\IndexUserUseCase;
use FlexPHP\Bundle\UserBundle\Domain\User\UseCase\ReadUserUseCase;
use FlexPHP\Bundle\UserBundle\Domain\User\UseCase\UpdateUserUseCase;
use FlexPHP\Bundle\UserBundle\Domain\User\UserFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USER_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexUserUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest() ? '@FlexPHPUser/user/_ajax.html.twig' : '@FlexPHPUser/user/index.html.twig';

        $request = new IndexUserRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'users' => $response->users,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USER_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(UserFormType::class);

        return $this->render('@FlexPHPUser/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USER_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateUserUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        $request = new CreateUserRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'user'));

        return $this->redirectToRoute('flexphp.user.users.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USER_READ')", statusCode=401)
     */
    public function read(ReadUserUseCase $useCase, int $id): Response
    {
        $request = new ReadUserRequest($id);

        $response = $useCase->execute($request);

        if (!$response->user->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPUser/user/show.html.twig', [
            'user' => $response->user,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USER_UPDATE')", statusCode=401)
     */
    public function edit(ReadUserUseCase $useCase, int $id): Response
    {
        $request = new ReadUserRequest($id);

        $response = $useCase->execute($request);

        if (!$response->user->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(UserFormType::class, $response->user);

        return $this->render('@FlexPHPUser/user/edit.html.twig', [
            'user' => $response->user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USER_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateUserUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        $form = $this->createForm(UserFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateUserRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'user'));

        return $this->redirectToRoute('flexphp.user.users.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USER_DELETE')", statusCode=401)
     */
    public function delete(DeleteUserUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        $request = new DeleteUserRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'user'));

        return $this->redirectToRoute('flexphp.user.users.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USERSTATUS_INDEX')", statusCode=401)
     */
    public function findUserStatus(Request $request, FindUserUserStatusUseCase $useCase): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new FindUserUserStatusRequest($request->request->all());

        $response = $useCase->execute($request);

        return new JsonResponse([
            'results' => $response->userStatus,
            'pagination' => ['more' => false],
        ]);
    }
}
