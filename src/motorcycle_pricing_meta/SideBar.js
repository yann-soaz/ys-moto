import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import EuroIcon from '../icons/Euro';
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
      icon={<EuroIcon/>}
      title="Informations sur la moto"
    >
      <PanelBody>
          <TextControl type="number" value={metas?.price} label="Prix" onChange={value => setMetas({...metas, price: value})}/>
          <TextControl type="number" value={metas?.reduced_price} label="Prix promotionnel" onChange={value => setMetas({...metas, reduced_price: value})}/>
          <ToggleControl 
            label="Type de vente"
            help={
                (metas?.occasion)
                    ? 'Est une occasion.'
                    : 'Est neuve.'
            }
            checked={ metas?.occasion }
            onChange={ (occasion) => {
              setMetas({...metas, occasion});
            } }
          />
          <ToggleControl 
            label="Permis A2"
            help={
                (metas?.a2)
                    ? 'Accessible avec le permis A2.'
                    : 'Interdite au permis A2.'
            }
            checked={ metas?.a2 }
            onChange={ (a2) => {
              setMetas({...metas, a2});
            } }
          />
      </PanelBody>
    </PluginDocumentSettingPanel>
  </>
 )
}