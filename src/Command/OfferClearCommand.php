<?php

namespace App\Command;

use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OfferClearCommand
 * @package App\Command
 */
class OfferClearCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:offer:clear';

    /**
     * OfferClearCommand constructor.
     * @param OfferRepository $repo
     * @param EntityManagerInterface $em
     */
    public function __construct(OfferRepository $repo, EntityManagerInterface $em)
    {
        parent::__construct();

        $this->repo = $repo;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            // Give a name for command
            ->setName('app:offer:clear')
            // Give a description for command
            ->setDescription('Commande qui sert a supprimer les offres expirés')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $now = new \DateTime();
        $offers = $this->repo->FindOffersForClear();
        $count = count($offers);

        foreach ($offers as $offer) {
            if ($offer->getExpiredAt() <= $now) {
                $this->em->remove($offer);
            }
        }

        $this->em->flush();

        if( $count == 1 ){
            $io->success("L'offre expirée a été supprimée");
        }

        elseif ( $count > 1 ){
            $io->success("$count offres expirées ont été supprimées");
        }

        else {
            $io->warning("Il n'y a pas d'offre a supprimer");
        }

        return 0;
    }
}
