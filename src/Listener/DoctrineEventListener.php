<?php

namespace App\EventListener;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

date_default_timezone_set('America/Sao_Paulo');

class DoctrineEventListener implements EventSubscriberInterface
{
    /**
     * This method can only return the event names; you cannot define a
     * custom method name to execute when each event triggers
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preRemove,
            Events::preUpdate
        ];
    }

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('persist', $args);
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $this->logActivity('remove', $args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->logActivity('update', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($action === 'persist') {
            if (property_exists($entity, 'createdAt') && property_exists($entity, 'modifiedAt')) {
                $entity->setCreatedAt(new \DateTime('NOW'));
                $entity->setModifiedAt(new \DateTime('NOW'));
            }
            if ($entity instanceof Account) {
                if (property_exists($entity, 'plainPassword')) {
                    $entity->setPassword(md5($entity->getPlainPassword()));
                    $entity->setEnabled(true);
                }
            }
        } else if ($action === 'update') {
            if (property_exists($entity, 'modifiedAt')) {
                $entity->setModifiedAt(new \DateTime('NOW'));
            }
        }
    }
}
