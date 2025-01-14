<?php

/**
 * This file is part of the PHP-FFmpeg-video-streaming package.
 *
 * (c) Amin Yazdanpanah <contact@aminyazdanpanah.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Streaming\Traits;

use Streaming\AutoReps;
use Streaming\Exception\InvalidArgumentException;
use Streaming\RepresentationInterface;
use Streaming\RepsCollection;

trait Representations
{

    /** @var RepsCollection */
    protected $repsBackLog;

    /** @var RepsCollection */
    protected $reps;

    /** @var bool */
    public $isAutoGenerateRepresentations;

    /**
     * add a representation
     * @param RepresentationInterface $rep
     * @return $this
     */
    public function addRepresentation(RepresentationInterface $rep)
    {
        $this->repsBackLog->add($rep);
        $this->reps->add($rep);
        return $this;
    }

    /**
     * add representations using an array
     * @param array $reps
     * @return $this
     */
    public function addRepresentations(array $reps)
    {
        array_walk($reps, [$this, 'addRepresentation']);
        return $this;
    }

    /**
     * @param array|null $sides
     * @param array|null $k_bitrate
     * @param string $sort
     * @return $this
     */
    public function autoGenerateRepresentations(array $sides = null, array $k_bitrate = null, string $sort = "asc")
    {
        $this->isAutoGenerateRepresentations = true;

        if (!$this->format) {
            throw new InvalidArgumentException('First you must set the format of the video');
        }

        $reps = new AutoReps($this->getMedia(), $this->getFormat(), $sides, $k_bitrate);
        $reps->sort($sort);

        foreach ($reps as $rep) {
            $this->addRepresentation($rep);
        }

        return $this;
    }

    /**
     * @return RepsCollection
     */
    public function getRepresentations(): RepsCollection
    {
        return $this->reps;
    }

    /**
     * @return RepsCollection
     */
    public function resetRepresentations(): RepsCollection
    {
        return $this->reps = new RepsCollection();
    }

    /**
     * add backlog representations for hls gpu transcoding using an array
     * @param array $reps
     * @return $this
     */
    public function addBackLogRepresentations(array $reps)
    {
        array_walk($reps, [$this, 'addBackLogRepresentation']);
        return $this;
    }

    /**
     * @return RepsCollection
     */
    public function getRepsBackLog()
    {
        return $this->repsBackLog;
    }

    /**
     * add a backLogRepresentation
     * @param RepresentationInterface $rep
     * @return $this
     */
    public function addBackLogRepresentation(RepresentationInterface $rep)
    {
        $this->repsBackLog->add($rep);
        return $this;
    }
}