<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\UserBundle\Domain\User;

use App\Form\Type\Select2Type;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\ReadUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UseCase\ReadUserStatusUseCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class UserFormType extends AbstractType
{
    private ReadUserStatusUseCase $readUserStatusUseCase;

    private UrlGeneratorInterface $router;

    public function __construct(
        ReadUserStatusUseCase $readUserStatusUseCase,
        UrlGeneratorInterface $router
    ) {
        $this->readUserStatusUseCase = $readUserStatusUseCase;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $statusIdModifier = function (FormInterface $form, ?string $value): void {
            $choices = null;

            if (!empty($value)) {
                $response = $this->readUserStatusUseCase->execute(new ReadUserStatusRequest($value));

                if ($response->userStatus->id()) {
                    $choices = [$response->userStatus->name() => $value];
                }
            }

            $form->add('statusId', Select2Type::class, [
                'label' => 'label.statusId',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('flexphp.user.users.find.user-status'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($statusIdModifier) {
            if (!$event->getData()) {
                return null;
            }

            $statusIdModifier($event->getForm(), $event->getData()->statusId());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($statusIdModifier): void {
            $statusIdModifier($event->getForm(), (string)$event->getData()['statusId'] ?: null);
        });

        $builder->add('email', InputType\TextType::class, [
            'label' => 'label.email',
            'required' => true,
            'attr' => [
                'minlength' => 6,
                'maxlength' => 80,
            ],
        ]);
        $builder->add('name', InputType\TextType::class, [
            'label' => 'label.name',
            'required' => true,
            'attr' => [
                'maxlength' => 80,
            ],
        ]);
        $builder->add('password', InputType\PasswordType::class, [
            'label' => 'label.password',
            'required' => true,
        ]);
        $builder->add('timezone', InputType\TimezoneType::class, [
            'label' => 'label.timezone',
            'required' => true,
        ]);
        $builder->add('statusId', Select2Type::class, [
            'label' => 'label.statusId',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('flexphp.user.users.find.user-status'),
                'maxlength' => 2,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'user',
        ]);
    }
}
