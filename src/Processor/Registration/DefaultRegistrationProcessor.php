<?php

declare(strict_types=1);

namespace App\Processor\Registration;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Service\Password\Hasher\DefaultPasswordHasher;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class DefaultRegistrationProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly DefaultPasswordHasher $hasher,
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $processor,
    ) {

    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$operation instanceof Post && !$data instanceof User) {
            return;
        }

        $hashedPassword = $this->hasher->hashPassword($data->getPassword(), $data);
        $data->setPassword($hashedPassword);

        $this->processor->process($data, $operation, $uriVariables, $context);

        return $data;
    }
}
