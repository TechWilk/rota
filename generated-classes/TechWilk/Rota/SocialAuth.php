<?php
namespace TechWilk\Rota;

use TechWilk\Rota\Base\SocialAuth as BaseSocialAuth;
use TechWilk\Rota\Map\SocialAuthTableMap;

/**
 * Skeleton subclass for representing a row from the 'cr_socialAuth' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SocialAuth extends BaseSocialAuth
{
    /**
     * Set the value of [meta] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\SocialAuth The current object (for fluent API support)
     */
    public function setMeta($v)
    {
        if ($v !== null) {
            if (is_string()) {
                $v = (string) $v;
            } else {
                $v = json_encode($v);
            }
        }

        if ($this->meta !== $v) {
            $this->meta = $v;
            $this->modifiedColumns[SocialAuthTableMap::COL_META] = true;
        }

        return $this;
    } // setMeta()

    /**
     * Get the [meta] column value.
     *
     * @return array
     */
    public function getMeta()
    {
        return json_decode($this->meta, true);
    }
}
