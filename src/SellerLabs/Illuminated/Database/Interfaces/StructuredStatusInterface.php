<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Database\Interfaces;

use SellerLabs\Illuminated\Database\Migrations\StatusReport;

/**
 * Interface StructuredStatusInterface.
 *
 * Provides status information about migrations in general. Specifically which
 * ones have been ran, are defined, need to be ran, and are unknown to the
 * application. Additionally, it is possible to get a full map of name to
 * class implementation for display purposes.
 *
 * This class is intended to be used on web UIs or CLI to show an overview of
 * migrations of an application.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database\Interfaces
 */
interface StructuredStatusInterface
{
    /**
     * Generate a report of the status of migrations.
     *
     * @return StatusReport
     */
    public function generateReport();

    /**
     * Get a complete mapping of aliases to migration implementations.
     *
     * @return array
     */
    public function getResolvedMap();
}
