import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { PanelBody, PanelRow, TextControl, DateTimePicker } from '@wordpress/components';
import MotoIcon from '../icons/Moto';
import { useEntityProp } from '@wordpress/core-data';
const { useSelect } = wp.data;



export default function MotorcycleSidebar () {

  const post_type = useSelect(
    (select) => select('core/editor').getCurrentPostType()
  )
  if (post_type != 'moto')
    return null;

  const [ metas, setMetas ] = useEntityProp( 'postType', post_type, 'meta' );

 return (
  <>
    <PluginDocumentSettingPanel
      name="metadata-sidebar" 
      icon={<MotoIcon/>}
      title="Informations sur la moto"
    >
      <PanelBody>
          <PanelRow>
            <TextControl type="string" value={metas?.model} label="Modèle" onChange={value => setMetas({...metas, model: value})}/>
          </PanelRow>
          <PanelRow>
            <TextControl type="number" value={metas?.engine_size} label="Cylindrée" onChange={value => setMetas({...metas, engine_size: value})}/>
          </PanelRow>
          <PanelRow>
            <TextControl type="string" value={metas?.mileage} label="Kilométrage" onChange={value => setMetas({...metas, mileage: value})}/>
          </PanelRow>
          <PanelRow>
          <TextControl type="date" value={(!!metas.first_registration) ? metas.first_registration : ''} label="première mise en circulation" onChange={first_registration => setMetas({...metas, first_registration})}/>
          </PanelRow>
      </PanelBody>
    </PluginDocumentSettingPanel>
  </>
 )
}