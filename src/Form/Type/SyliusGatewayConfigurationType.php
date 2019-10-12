<?php

declare(strict_types=1);

namespace MCRO\SyliusNMIPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class SyliusGatewayConfigurationType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder->add('username', TextType::class);
    $builder->add('password', PasswordType::class);
    $builder->add('tokenization', TextType::class);
    $builder->add('api_key', TextType::class);
  }
}
