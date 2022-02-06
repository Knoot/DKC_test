<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $repository;

    private RoleRepository $roleRepository;

    public function __construct(UserRepository $repository, RoleRepository $roleRepository)
    {
        $this->repository = $repository;
        $this->roleRepository = $roleRepository;
    }

    #[Route('/user', name: 'user_list', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->repository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = $user->toArray();
        }

        return $this->render('user/list.html.twig', [
            'users' => $data,
        ]);
    }

    #[Route('/user/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user->toArray(),
        ]);
    }

    #[Route('/user/new', name: 'add_user_page', methods: ['GET'])]
    public function createPage(): Response
    {
        return $this->render('user/create.html.twig', [
            'roles' => $this->getRoleList()
        ]);
    }

    #[Route('/user/new', name: 'add_user', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $username = $request->get('name');
        $userRole = $this->roleRepository->find($request->get('role'));

        $user = new User();
        $user->setName($username);
        $user->setRole($userRole);

        $this->repository->save($user);

        return $this->redirect($this->generateUrl('user_list'));
    }

    #[Route('/user/edit/{id}', name: 'edit_user_page', methods: ['GET'])]
    public function editPage(User $user): Response
    {
        return $this->render('user/edit.html.twig', [
            'user' => $user->toArray(),
            'roles' => $this->getRoleList()
        ]);
    }

    #[Route('/user/edit/{id}', name: 'edit_user', methods: ['POST'])]
    public function edit(User $user, Request $request): Response
    {
        $username = $request->get('name');
        $userRole = $this->roleRepository->find($request->get('role'));

        $user->setName($username);
        $user->setRole($userRole);

        $this->repository->save($user);

        return $this->redirect($this->generateUrl('user_list'));
    }

    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(User $user): Response
    {
        $this->repository->delete($user);

        return $this->redirect($this->generateUrl('user_list'));
    }

    private function getRoleList(): array
    {
        return array_map(function ($role) {
            return [
                'id' => $role->getId(),
                'title' => $role->getTitle(),
            ];
        }, $this->roleRepository->findAll());
    }
}
