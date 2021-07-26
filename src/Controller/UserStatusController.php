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

use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\CreateUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\DeleteUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\IndexUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\ReadUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\UpdateUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UseCase\CreateUserStatusUseCase;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UseCase\DeleteUserStatusUseCase;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UseCase\IndexUserStatusUseCase;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UseCase\ReadUserStatusUseCase;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UseCase\UpdateUserStatusUseCase;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UserStatusFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserStatusController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USERSTATUS_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexUserStatusUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest() ? '@FlexPHPUser/userStatus/_ajax.html.twig' : '@FlexPHPUser/userStatus/index.html.twig';

        $request = new IndexUserStatusRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'userStatus' => $response->userStatus,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USERSTATUS_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(UserStatusFormType::class);

        return $this->render('@FlexPHPUser/userStatus/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USERSTATUS_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateUserStatusUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(UserStatusFormType::class);
        $form->handleRequest($request);

        $request = new CreateUserStatusRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'userStatus'));

        return $this->redirectToRoute('flexphp.user.user-status.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USERSTATUS_READ')", statusCode=401)
     */
    public function read(ReadUserStatusUseCase $useCase, string $id): Response
    {
        $request = new ReadUserStatusRequest($id);

        $response = $useCase->execute($request);

        if (!$response->userStatus->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPUser/userStatus/show.html.twig', [
            'userStatus' => $response->userStatus,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USERSTATUS_UPDATE')", statusCode=401)
     */
    public function edit(ReadUserStatusUseCase $useCase, string $id): Response
    {
        $request = new ReadUserStatusRequest($id);

        $response = $useCase->execute($request);

        if (!$response->userStatus->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(UserStatusFormType::class, $response->userStatus);

        return $this->render('@FlexPHPUser/userStatus/edit.html.twig', [
            'userStatus' => $response->userStatus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USERSTATUS_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateUserStatusUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $form = $this->createForm(UserStatusFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateUserStatusRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'userStatus'));

        return $this->redirectToRoute('flexphp.user.user-status.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USERSTATUS_DELETE')", statusCode=401)
     */
    public function delete(DeleteUserStatusUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $request = new DeleteUserStatusRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'userStatus'));

        return $this->redirectToRoute('flexphp.user.user-status.index');
    }
}
