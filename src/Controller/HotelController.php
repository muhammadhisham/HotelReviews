<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Hotel;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Doctrine\Common\Collections\Criteria;


class HotelController extends Controller
{
    /**
     * @Route("/{hotelid}/today/review", name="hotel", requirements={"hotelid"="\d+"})
     */
    public function index($hotelid)
    {
        // Server side file system cache
        $cache = new FilesystemCache();

        $hotel = $this->getDoctrine()
        ->getRepository(Hotel::class)
        ->find($hotelid);

        // 404 error if hotel id not found
        if (!$hotel) {
            throw $this->createNotFoundException(
                'The hotel id was not found'
            );
        }

        // Check if review exists in cache
        if (!$cache->has(strval($hotel->getId()))) {
            $reviews = $hotel->getReviews();

            // Get reviews for today
            $now = new \DateTime();

            $from = new \DateTime($now->format("Y-m-d")." 00:00:00");
            $to   = new \DateTime($now->format("Y-m-d")." 23:59:59");


            $criteria = Criteria::create()
                ->where(Criteria::expr()->gte("review_date", $from))
                ->andWhere(Criteria::expr()->lte("review_date", $to))
            ;

            $todayReviews = $reviews->matching($criteria)->toArray();


            // Get a random review from today's reviews
            $randomIndex = array_rand($todayReviews);

            $randomReview = $todayReviews[$randomIndex];

            // Save review on server side for 1 minute
            $cache->set(strval($hotel->getId()), $randomReview, 60);
        } else {
            $randomReview = $cache->get(strval($hotel->getId()));
        }       

        $response = $this->render('hotel/index.html.twig', [
            'hotel_name' => $hotel->getName(),
            'review' => $randomReview
        ]);

        // Client side cache for 5 minutes
        $response->setSharedMaxAge(300);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }
}
