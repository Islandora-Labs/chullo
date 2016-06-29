<?php

/**
 * This file is part of Islandora.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP Version 5.5.9
 *
 * @category Islandora
 * @package  Islandora
 * @author   Daniel Lamb <daniel@discoverygarden.ca>
 * @author   Nick Ruest <ruestn@gmail.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */

namespace Islandora\Chullo;

/**
 * Interface for Fedora interaction.
 *
 * @category Islandora
 * @package  Islandora
 * @author   Daniel Lamb <daniel@discoverygarden.ca>
 * @author   Nick Ruest <ruestn@gmail.com>
 * @author   Diego Pino <dpino@metro.org>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */
interface IFedoraClient extends IFedoraApi
{
    /**
     * Gets RDF metadata from Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     *
     * @return EasyRdf_Graph
     */
    public function getGraph(
        $uri = "",
        $headers = [],
        $transaction = ""
    );

    /**
     * Saves RDF in Fedora.
     *
     * @param string            $uri            Resource URI
     * @param EasyRdf_Resource  $rdf            RDF to save
     * @param string            $transaction    Transaction id
     *
     * @return null
     */
    public function saveGraph(
        $uri,
        \EasyRdf_Graph $graph,
        $transaction = ""
    );
}
