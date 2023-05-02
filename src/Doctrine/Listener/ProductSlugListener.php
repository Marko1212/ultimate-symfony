<?php

namespace App\Doctrine\Listener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSlugListener
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function prePersist(Product $entity, LifecycleEventArgs $event)
    {
        if (empty($entity->getSlug())) {
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }

    public function preUpdate(Product $entity, LifecycleEventArgs $event)
    {
        $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
    }
}
