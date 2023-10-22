<?php

namespace App\Service;

use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Address;

class FetchFruitsService
{
    private $apiUrl;
    private $client;
    private $mailer;
    private $successfulInsert;
    private const FETCH_ALL = '/fruit/all';

    public function __construct(
        MailerService $mailer,
        EntityManagerInterface $entityManager,
        string $apiUrl
    ) {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->client = new \GuzzleHttp\Client();
        $this->successfulInsert = 0;
        $this->apiUrl = $apiUrl;
    }

    public function fetch(): void
    {
        $url = $this->apiUrl.self::FETCH_ALL;
        $response = $this->client->request('GET', $url);
        $contents = json_decode($response->getBody()->getContents(), true);

        foreach ($contents as $fruit) {
            $repository = $this->entityManager->getRepository(Fruit::class);
            $dataEntity = $repository->find($fruit['id']);
            if ($dataEntity) {
                continue;
            }

            $entity = new Fruit();
            $entity->setId($fruit['id']);
            $entity->setName($fruit['name']);
            $entity->setFamily($fruit['name']);
            $entity->setFruitOrder($fruit['order']);
            $entity->setNutritions($fruit['nutritions']);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            ++$this->successfulInsert;
        }

        $this->sendEmailNotification();
    }

    private function sendEmailNotification(): void
    {
        $totalCount = $this->getSuccessfulInsert();
        $body = <<<EMAILBODY
Hello,

This email is to notify you that Fruits data has been added to Database,
with total records of [{$totalCount}], Viola!

Thanks

EMAILBODY;

        $mailParams = [
            'from' => new Address('mugiwaradev1109@gmail.com', 'Dev Mugiwara'),
            'to' => 'jemmydpuerto@gmail.com',
            'subject' => 'Fruits Project',
            'body' => $body,
        ];

        $this->mailer->send($mailParams);
    }

    public function getSuccessfulInsert(): int
    {
        return $this->successfulInsert;
    }
}
