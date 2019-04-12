<?php

namespace App\Entity;

use Beelab\TagBundle\Tag\TaggableInterface;
use Beelab\TagBundle\Tag\TagInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @Vich\Uploadable
 */
class Product implements TaggableInterface
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="text", length=128)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     */
    private $tags;

    private $tagsText;

    /**
     * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private $tsCreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $tsUpdate;

    public function __construct() {
        $this->tsCreate = new \DateTimeImmutable();
        $this->tags = new ArrayCollection();
    }

    // Getters
    public function getDescription(): ?string {
        return $this->description;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function getImageFile(): ?File {
        return $this->imageFile;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function getTags(): iterable {
        return $this->tags;
    }

    public function getTagNames(): array {
        return empty($this->tagsText) ? [] : \array_map('trim', explode(',', $this->tagsText));
    }

    public function getTagsText(): ?string {
        $this->tagsText = \implode(', ', $this->tags->toArray());
        return $this->tagsText;
    }

    public function getTsCreate(): ?DateTime {
        return $this->tsCreate->format('Y-m-d H:i:s');
    }

    // Setters
    public function setDescription(?string $description): void {
        $this->description = $description;
        if ($description) {
            $this->tsUpdate = new \DateTimeImmutable();
        }
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }

    public function setImageFile(?File $image = null): void {
        $this->imageFile = $image;
        if ($image) {
            $this->tsUpdate = new \DateTimeImmutable();
        }
    }

    public function setName(?string $name): void {
        $this->name = $name;
        if ($name) {
            $this->tsUpdate = new \DateTimeImmutable();
        }
    }

    public function addTag(TagInterface $tag): void {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function removeTag(TagInterface $tag): void {
        $this->tags->removeElement($tag);
    }

    public function hasTag(TagInterface $tag = NULL): bool {
        return $this->tags->contains($tag);
    }

    public function setTagsText(?string $tagsText): void {
        $this->tagsText = $tagsText;
        $this->tsUpdate = new \DateTimeImmutable();
    }

}
