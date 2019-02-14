<?php
/**
 * @package  FS_CURL
 * @subpackage FS_CURL_Configuration
 * distributor.conf.php
 */

/**
 * @package  FS_CURL
 * @subpackage FS_CURL_Configuration
 * @license
 * @author Raymond Chandler (intralanman) <intralanman@gmail.com>
 * @version 0.1
 * Class to write the XML for distributor.conf
 */
class distributor_conf extends fs_configuration {

    public function distributor_conf() {
        $this -> fs_configuration();
    }

    public function main() {
        $distributor = $this -> get_distributor();
        $this -> write_distributor($distributor);
    }

    /**
     * Write individual node elements with their attributes
     *
     * @param array $node_attributes
     */
    private function write_node($node_attributes) {
        $this -> xmlw -> startElement('node');
        $this -> xmlw -> writeAttribute('name', $node_attributes['name']);
        $this -> xmlw -> writeAttribute('weight', $node_attributes['weight']);
        $this -> xmlw -> endElement();
    }

    /**
     * Fetch the distributor data from the database
     *
     * @return array $distributor_data
     */
    private function get_distributor() {
        $query = sprintf(
        'SELECT * FROM distributor_lists al JOIN distributor_nodes an ON an.list_id=al.id;'
        );
        $distributor_data = $this -> db -> queryAll($query);
        if (FS_PDO::isError($profiles)) {
            $this -> comment($query);
            $this -> comment($this -> db -> getMessage());
            return array();
        }
        return $distributor_data;
    }

    /**
     * Write distributor data out
     *
     * @param array $distributor
     */
    private function write_distributor($distributor) {
        $this -> xmlw -> startElement('configuration');
        $this -> xmlw -> writeAttribute('name', 'distributor.conf');
        $this -> xmlw -> writeAttribute('description', 'Trunk Lists');
        $this -> xmlw -> startElement('lists');
        $node_count = count($distributor);
        for ($i=0; $i<$node_count; $i++) {
            $last = $i - 1;
            $next = $i + 1;
            if ($last < 0 || $distributor[$last]['distributor_name'] != $distributor[$i]['distributor_name']) {
                $this -> xmlw -> startElement('list');
                $this -> xmlw -> writeAttribute('name', $distributor[$i]['distributor_name']);
            }
            $this -> write_node($distributor[$i]);
            if ((!array_key_exists($next, $distributor))
            || $distributor[$next]['distributor_name'] != $distributor[$i]['distributor_name']) {
                $this -> xmlw -> endElement();
            }
        }
        $this -> xmlw -> endElement();
        $this -> xmlw -> endElement();
    }
}
?>