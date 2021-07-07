<?php declare(strict_types=1);

namespace SwagTraining\AuthorsPageLoader\Event\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Storefront\Page\GenericPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddAuthorsToPage implements EventSubscriberInterface
{
    private EntityRepositoryInterface $authorRepository;

    /**
     * AddAuthorsToPage constructor.
     * @param EntityRepositoryInterface $authorRepository
     */
    public function __construct(
        EntityRepositoryInterface $authorRepository
    ) {
        $this->authorRepository = $authorRepository;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            GenericPageLoadedEvent::class => 'addAuthors'
        ];
    }

    /**
     * @param GenericPageLoadedEvent $event
     */
    public function addAuthors(GenericPageLoadedEvent $event): void
    {
        $context = $event->getContext();
        $criteria = new Criteria;
        $criteria->setLimit(5);
        $criteria->addSorting(new FieldSorting('birthdate', 'DESC'));

        $authors = $this->authorRepository->search($criteria, $context);
        $event->getPage()->addExtension('authors', $authors);
    }
}
