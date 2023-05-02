<?php

namespace App\Doctrine\Listener;

use App\Entity\Category;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugListener
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function prePersist(Category $entity)
    {
        if (empty($entity->getSlug())) {
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }

    public function preUpdate(Category $entity)
    {
        $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
    }
}
