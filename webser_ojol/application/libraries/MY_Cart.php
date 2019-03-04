<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Cart extends CI_Cart
{
    function __construct()
    {
       parent::__construct();
    }

    function update_all($items = array())
    {
        // Was any cart data passed?
        if ( ! is_array($items) OR count($items) == 0)
        {
            return false;
        }

        // You can either update a single product using a one-dimensional array,
        // or multiple products using a multi-dimensional one.  The way we
        // determine the array type is by looking for a required array key named "rowid".
        // If it's not found we assume it's a multi-dimensional array
        if (isset($items['rowid']))
        {
            $this->_update_item($items);
        }
        else
        {
            foreach($items as $item)
            {
                $this->_update_item($item);
            }
        }

        $this->_save_cart();
    }

    /*
     * Function: _update_item
     * Param: Array with a rowid and information about the item to be updated
     *             such as qty, name, price, custom fields.
     */
    function _update_item($item)
    {
        foreach($item as $key => $value)
        {
            //don't allow them to change the rowid
            if($key == 'rowid')
            {
                continue;
            }

            //do some processing if qty is
            //updated since it has strict requirements
            if($key == "qty")
            {
                // Prep the quantity
                $item['qty'] = preg_replace('/([^0-9])/i', '', $item['qty']);

                // Is the quantity a number?
                if ( ! is_numeric($item['qty']))
                {
                    continue;
                }

                // Is the new quantity different than what is already saved in the cart?
                // If it's the same there's nothing to do
                if ($this->_cart_contents[$item['rowid']]['qty'] == $item['qty'])
                {
                    continue;
                }

                // Is the quantity zero?  If so we will remove the item from the cart.
                // If the quantity is greater than zero we are updating
                if ($item['qty'] == 0)
                {
                    unset($this->_cart_contents[$item['rowid']]);
                    continue;
                }
            }

            $this->_cart_contents[$item['rowid']][$key] = $value;
        }
    }
}