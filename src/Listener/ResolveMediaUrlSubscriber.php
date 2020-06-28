<?php

namespace App\Listener;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Media;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

class ResolveMediaUrlSubscriber implements EventSubscriberInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function onPreSerialize(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        if (
            !($attributes = RequestAttributesExtractor::extractAttributes($request))
            || !\is_a($attributes['resource_class'], Media::class, true)
        ) {
            return;
        }

        $media = $controllerResult;

        if (!is_iterable($media)) {
            $media = [$media];
        }

        foreach ($media as $mediaObject) {
            if (!$mediaObject instanceof Media) {
                continue;
            }

            $mediaObject->setUrl($this->storage->resolveUri($mediaObject, 'file'));
        }
    }
}
