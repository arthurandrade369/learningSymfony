<?php

namespace App\Listener;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DoctrineEventListener implements EventSubscriberInterface
{
    public const BRT = 'America/Sao_Paulo';

    /**
     * This method can only return th event names; you cannot define a
     * custom method name to execute when each event triggers
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate
        ];
    }

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself
    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('persist', $args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->logActivity('remove', $args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->logActivity('update', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($action === 'persist') {
            if (property_exists($entity, 'createdAt') && property_exists($entity, 'modifiedAt')) {
                $entity->setCreatedAt(new \DateTime('now', new \DateTimeZone(self::BRT)));
                $entity->setModifiedAt(new \DateTime('now', new \DateTimeZone(self::BRT)));
            }
            if ($entity instanceof Account) {
                if (property_exists($entity, 'plainPassword')) {
                    $entity->setPassword(md5($entity->getPlainPassword()));
                }
            }
        } else if ($action === 'update') {
            if (property_exists($entity, 'modifiedAt')) {
                $entity->setModifiedAt(new \DateTime('now', new \DateTimeZone(self::BRT)));
            }
        }
    }
}
