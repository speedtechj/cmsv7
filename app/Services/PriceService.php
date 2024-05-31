<?php
namespace App\Services;

use App\Models\Agent;
use App\Models\Boxtype;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Discount;
use App\Models\Zoneprice;
use App\Models\Agentprice;
use App\Models\Agentdiscount;
use Illuminate\Support\Number;
use App\Models\Receiveraddress;


class PriceService
{

    public $serviceid;
    public $zoneid;
    public $boxtypeid;
    public $quantity;
    public $agentid;
    public $extracharge;
    public $discount_amount;
    public $totalinch;
    public $receiveraddressid;
    public $irregularprice;
    public $price;
    public $totalprice;
    public function computePrice()
    {


        if ($this->serviceid == 2) {
            if (
                $this->serviceid != null && $this->zoneid != null && $this->boxtypeid != null
                && $this->quantity != null
            ) {
                
                $this->price = Zoneprice::Officeprice($this->serviceid, $this->zoneid, $this->boxtypeid, $this->quantity);
                $this->totalprice = ($this->price * $this->quantity * $this->irregularprice) 
                + $this->extracharge 
                + $this->totalinch 
                - $this->discount_amount;
            } else {
                // } else {
                return 0;
            }
        }

        if ($this->serviceid == 1 && $this->agentid != null) {
            $agent_type = Agent::Agenttype($this->agentid);
            if ($agent_type == 1) {
                if (
                    $this->serviceid != null && $this->zoneid != null && $this->boxtypeid != null
                    && $this->quantity != null
                ) {

                    $this->price = Zoneprice::Officeprice($this->serviceid, $this->zoneid, $this->boxtypeid, $this->quantity);
                    $this->totalprice = ($this->price * $this->quantity * $this->irregularprice) 
                + $this->extracharge 
                + $this->totalinch 
                - $this->discount_amount;
                       
                    // return $price->price * $this->quantity + $this->totalinch + $this->extracharge - $this->discount_amount;

                } else {
                    return 0;
                }
            } else {
                $this->price = Agentprice::Agentprice($this->serviceid, $this->zoneid, $this->boxtypeid, $this->quantity, $this->agentid);
                $this->totalprice = ($this->price * $this->quantity * $this->irregularprice) 
                + $this->extracharge 
                + $this->totalinch 
                - $this->discount_amount;

                // return $price->price * $this->quantity + $this->totalinch + $this->extracharge - $this->discount_amount;
            }
        }

    }

    public function calculatePrice($state, $get, $set)
    {
       

        if ($get('boxtype_id') != null) {
            $this->quantity = Boxtype::Totalbox($get('boxtype_id'));
        } else {
            $totalbox = 0;
        }
       
        $this->extracharge = floatval($get('extracharge_amount'));
        $this->receiveraddressid = $get('receiveraddress_id');
        $this->zoneid = Receiveraddress::Zoneid($this->receiveraddressid);

        $this->serviceid = $get('servicetype_id');

        $this->boxtypeid = $get('boxtype_id');
        $this->agentid = $get('agent_id');
        $this->Agenttype($set, $get);
        $this->Totalinches($set, $get);
        $this->Computeirregular($set, $get);
        $this->computePrice();
        $set('zone_id', $this->zoneid);
        $set('total_price', round(floatval($this->totalprice), precision: 2));
        // $result = collect([$servicetypeid, $zone, $boxtypeid, $totalbox, $agentid, $extracharge, $amount_discount, $totalinches]);
        // return $result;

    }

    public function Extracharge($set, $get, $state)
    {
        if ($get('catextracharge_id') != null) {
            $this->extracharge = $get('extracharge_amount');
        } else {
            $set('extracharge_amount', null);
        }

    }

    public function Totalinches($set, $get)
    {

        if ($get('boxtype_id') == '9') {
            if ($get('total_inches') != null) {
                $this->totalinch = $get('total_inches') * 6;
            }
        } else {
            $set('total_inches', null);
            $this->totalinch = 0;
        }
    }
    public function officediscount($set, $get)
    {


        if ($get('discount_id') != null) {
            $this->discount_amount = Discount::Discountamount($get('discount_id'));
        } else {
            $this->discount_amount = null;
        }
        if ($get('agentdiscount_id') != null) {
            $set('agentdiscount_id', null);
        }
    }

    public function Agentdiscount($set, $get)
    {
        if ($get('agentdiscount_id') != null) {
            $this->discount_amount = Agentdiscount::Agentdiscountamount($get('agentdiscount_id'));
        } else {
            $this->discount_amount = null;

        }

        if ($get('discount_id') != null) {
            $set('discount_id', null);
        }
    }
    public function Resetdiscount($set, $get)
    {
        if ($get('discount_id') != null || $get('agentdiscount_id') != null) {
            $set('discount_id', null);
            $set('agentdiscount_id', null);
        }
    }

    public function Computeirregular($set, $get)
    {
        if ($get('boxtype_id') == '4') {
            if (
                $get('irregular_length') != null
                && $get('irregular_width') != null
                && $get('irregular_height') != null
            ) {
                $irregtotal = $get('irregular_length') * $get('irregular_width') * $get('irregular_height') / 9720;
                $this->irregularprice = $irregtotal;
            }
        } else {
            $this->irregularprice = 1;
            $set('irregular_length', null);
            $set('irregular_width', null);
            $set('irregular_height', null);
        }
       
    }
    public function Discountreset($set, $get) :void
    {
        $set('discount_id', null);
        $set('agentdiscount_id', null);

    }
    public function Agenttype($set, $get)
    {
        if ($get('agent_id') != null) {
            $agent_type = Agent::Agenttype($get('agent_id'));
            if ($agent_type == 0) {
                $set('discount_flag', 0);
            } else {
                $set('discount_flag', 1);
            }
        }
        if ($get('servicetype_id') == 2) {
            $set('discount_flag', 1);
            $set('agent_id', null);
           

        }
    }

    

}