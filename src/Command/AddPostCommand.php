<?php

namespace App\Command;

use App\Entity\Post;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddPostCommand extends Command
{
    protected static $defaultName = 'app:add-post';
    protected static $defaultDescription = 'Add the post';
    private EntityManagerInterface $em;
    private PostRepository $postRepository;

    public function __construct(EntityManagerInterface $em, PostRepository $postRepository)
    {
        parent::__construct();
        $this->em = $em;
        $this->postRepository = $postRepository;
    }


    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Create post');
        $text = $io->ask('Please enter the text of the post: ', '', function ($text) {
            if ($text == '') {
                throw new RuntimeException('Text is not be empty.');
            }
            return $text;
        });
        $alias = $io->ask('Please enter the alias of the post (optional): ', '', function ($alias) {
            $hasAlias = $this->postRepository->findOneBy(['alias' => $alias]);
            if (null !== $hasAlias) {
                throw new RuntimeException('This value is already used.');
            }
            return $alias;
        });
        $post = new Post();
        $post->setText($text)
            ->setAlias($alias)
            ->setCreatedAt(new DateTime());
        $this->em->persist($post);
        $this->em->flush();
        $io->success('Post successfully generated!');
        return Command::SUCCESS;
    }
}
