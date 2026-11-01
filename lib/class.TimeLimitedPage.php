<?php
namespace CGContentUtils;
use Content;
use cms_utils;

abstract class base_time_limited_page extends Content
{
    const PROP_ST = '__cgcu_astart_time';
    const PROP_ET = '__cgcu_bend_time';

    public function Type()
    {
        return 'cgcu_timelimited_page';
    }

    public function FriendlyName()
    {
        $mod = cms_utils::get_module(MOD_CGCONTENTUTILS);
        return $mod->Lang('timelimited_page_type');
    }

    public function GetAdminInfo()
    {
        $mod = cms_utils::get_module(MOD_CGCONTENTUTILS);
        $out = null;
        $st = $this->GetPropertyValue(self::PROP_ST);
        if( $st ) $out = $mod->Lang('display_from').' '.strftime('%x',$st);
        $et = $this->GetPropertyValue(self::PROP_ET);
        if( $et ) {
            if( !$out ) {
                $out .= $mod->Lang('display_until').' '.strftime('%x',$et);
            }
            else {
                $out .= ' '.$mod->Lang('display_to').' '.strftime('%x',$et);
            }
        }
        return trim($out);
    }

    public function SetProperties()
    {
        parent::SetProperties();
        $this->RemoveProperty('cachable',false);
        $this->AddProperty(self::PROP_ST,1,self::TAB_PERMS);
        $this->AddProperty(self::PROP_ET,1,self::TAB_PERMS);
    }

    public function IsViewable()
    {
        $st = $this->GetPropertyValue(self::PROP_ST);
        $et = $this->GetPropertyValue(self::PROP_ET);
        $now = time();
        // if there is no start time, AND no end time... then it is viewable.
        if( $st && $now < $st ) return;
        if( $et && $now > $et ) return;
        return true;
    }

    public function ShowInMenu()
    {
        return parent::ShowInMenu() && $this->IsViewable();
    }

    protected function _display_single_element($one,$adding)
    {
        $mod = cms_utils::get_module(MOD_CGCONTENTUTILS);
        switch( $one ) {
        case self::PROP_ST:
            $out = [ $mod->Lang('display_start_at') ];
            $st = $this->GetPropertyvalue(self::PROP_ST);
            if( $st ) $st = strftime('%Y-%m-%d',$st);
            $inp = '<input type="date" name="%s" placeholder="yyyy/mm/dd" value="%s"/>';
            $out[] = sprintf($inp,self::PROP_ST,$st);
            return $out;
        case self::PROP_ET:
            $out = [ $mod->Lang('display_ends_at') ];
            $et = $this->GetPropertyvalue(self::PROP_ET);
            if( $et ) $et = strftime('%Y-%m-%d',$et);
            $inp = '<input type="date" name="%s" placeholder="yyyy/mm/dd" value="%s"/>';
            $out[] = sprintf($inp,self::PROP_ET,$et);
            return $out;
        default:
            return parent::display_single_element($one,$adding);
        }
    }
} // class

if( version_compare(CMS_VERSION,'2.2.900') < 1) {

    class TimeLimitedPage extends base_time_limited_page
    {
        public function FillParams($params,$editing = false)
        {
            if( isset($params[self::PROP_ST]) ) {
                $ts = strtotime($params[self::PROP_ST].' 00:00:00');
                $this->SetPropertyValue(self::PROP_ST,$ts);
            }
            if( isset($params[self::PROP_ET]) ) {
                $ts = strtotime($params[self::PROP_ET].' 23:59:59');
                $this->SetPropertyValue(self::PROP_ET,$ts);
            }

            parent::FillParams($params,$editing);
            $this->SetCachable(false);
        }

        protected function display_single_element($one, $adding)
        {
            return parent::_display_single_element($one,$adding);
        }
    } // class

} else {

    class TimeLimitedPage extends base_time_limited_page
    {
        public function FillParams(array $params,bool $editing = null)
        {
            if( isset($params[self::PROP_ST]) ) {
                $ts = strtotime($params[self::PROP_ST].' 00:00:00');
                $this->SetPropertyValue(self::PROP_ST,$ts);
            }
            if( isset($params[self::PROP_ET]) ) {
                $ts = strtotime($params[self::PROP_ET].' 23:59:59');
                $this->SetPropertyValue(self::PROP_ET,$ts);
            }
            //debug_display(strftime('%x %X',$this->GetPropertyValue(self::PROP_ST)),'start');
            //debug_display(strftime('%x %X',$this->GetPropertyValue(self::PROP_ET)),'end');

            parent::FillParams($params,$editing);
            $this->SetCachable(false);
        }

        protected function display_single_element(string $one, bool $adding)
        {
            return parent::_display_single_element($one,$adding);
        }
    } // class

}
