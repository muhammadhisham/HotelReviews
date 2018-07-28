<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @ORM\Column(type="text")
     */
    private $review;

    /**
     * @ORM\Column(type="datetime")
     */
    private $review_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hotel", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hotelid;

    public function getId()
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(string $review): self
    {
        $this->review = $review;

        return $this;
    }

    public function getReview_date(): ?datetime
    {
        return $this->review_date;
    }

    public function setReview_date(datetime $review_date): self
    {
        $this->review_date = $review_date;

        return $this;
    }

    public function getHotelid(): ?Hotel
    {
        return $this->hotelid;
    }

    public function setHotelid(?Hotel $hotelid): self
    {
        $this->hotelid = $hotelid;

        return $this;
    }
}
