<?php
namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserCrudController extends AbstractCrudController
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getEntityFqcn(): string
    {
        // Spécifier l'entité associée au contrôleur CRUD
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Configurer les champs du formulaire pour chaque page
        return [
            TextField::new('username'),
            TextField::new('email'),
            TextareaField::new('description'),
            AssociationField::new('classe'),
            AssociationField::new('spe'),
            TextField::new('password'),

                
            // Ajouter le champ "roles"
            ChoiceField::new('roles')
                ->setChoices([
                    'Admin' => ['ROLE_ADMIN'],
                    'Rooster' => ['ROLE_ROOSTER'],
                    'Membre' => ['ROLE_MEMBER'],
                    'User' => ['ROLE_USER'],
                    ])
                ->allowMultipleChoices(),

        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    // Autoriser uniquement les administrateurs à créer des utilisateurs
    if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Access Denied.');
    }

    $newPassword = $entityInstance->getPassword();

    if (!empty($newPassword)) {
        // Hacher le nouveau mot de passe avant de persister l'entité
        $hashedPassword = $this->passwordEncoder->hashPassword($entityInstance, $newPassword);
        $entityInstance->setPassword($hashedPassword);
    }

    parent::persistEntity($entityManager, $entityInstance);
}

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Autoriser uniquement les administrateurs à modifier des utilisateurs
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access Denied.');
        }

        // Supprimer le champ "password" de la requête pour éviter sa mise à jour
        $request = Request::createFromGlobals();
        $request->request->remove('password');

        parent::updateEntity($entityManager, $entityInstance);
    }


    public function configureActions(Actions $actions): Actions
    {
        // Configurer les actions disponibles pour le contrôleur CRUD
        $actions = parent::configureActions($actions);

        if (!$this->isGranted('ROLE_ADMIN')) {
            
            // Désactiver les actions "NEW", "EDIT" et "DELETE" pour les utilisateurs non administrateurs
            $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
        }

        return $actions;
    }
}