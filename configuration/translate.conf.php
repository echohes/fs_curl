<?php

class translate_conf extends fs_configuration {

    public function translate_conf() {
        $this -> fs_configuration();
    }

    public function main() {
        $translate = $this -> get_translate();
        $this -> write_translate($translate);
    }

    /**
     * Write individual node elements with their attributes
     *
     * @param array $node_attributes
     */
    private function write_node($node_attributes) {
        $this -> xmlw -> startElement('rule');
        $this -> xmlw -> writeAttribute('regex', $node_attributes['rule']);
        $this -> xmlw -> writeAttribute('replace', $node_attributes['replace']);
        $this -> xmlw -> endElement();
    }

    /**
     * Fetch the translate data from the database
     *
     * @return array $translate_data
     */
    private function get_translate() {
        $query = sprintf(
        'SELECT * FROM translate_profile al JOIN translate_rules an ON an.profile_id=al.id;'
        );
        $translate_data = $this -> db -> queryAll($query);
        if (FS_PDO::isError($profiles)) {
            $this -> comment($query);
            $this -> comment($this -> db -> getMessage());
            return array();
        }
        return $translate_data;
    }

    /**
     * Write translate data out
     *
     * @param array $translate
     */
    private function write_translate($translate) {
        $this -> xmlw -> startElement('configuration');
        $this -> xmlw -> writeAttribute('name', 'translate.conf');
        $this -> xmlw -> writeAttribute('description', 'Translate Numbers');
        $this -> xmlw -> startElement('profiles');
        $node_count = count($translate);
        for ($i=0; $i<$node_count; $i++) {
            $last = $i - 1;
            $next = $i + 1;
            if ($last < 0 || $translate[$last]['name'] != $translate[$i]['name']) {
                $this -> xmlw -> startElement('profile');
                $this -> xmlw -> writeAttribute('name', $translate[$i]['name']);
            }
            $this -> write_node($translate[$i]);
            if ((!array_key_exists($next, $translate))
            || $translate[$next]['name'] != $translate[$i]['name']) {
                $this -> xmlw -> endElement();
            }
        }
        $this -> xmlw -> endElement();
        $this -> xmlw -> endElement();
    }
}
?>