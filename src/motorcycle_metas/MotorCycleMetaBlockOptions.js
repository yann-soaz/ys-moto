import { PanelBody, SelectControl, RangeControl } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
import MultipleCheckBoxControl from '../MultipleCheckBoxControl';
import { __ } from '@wordpress/i18n';

export default function MotorCycleMetaBlockOption ({attributes,setAttributes}) {
  const editChoices = (name, checked) => {
		if (checked) {
			setAttributes({visible_metas: [...attributes.visible_metas, name]})
		} else {
			setAttributes({visible_metas: attributes.visible_metas.filter(e => e !== name)})
		}
	}
	const getChoices = () => {
		const choices = [
			['model', __('Modèle', 'ys-moto')],
			['engine_size', __('Cylindrée', 'ys-moto')],
			['mileage', __('Kilométrage', 'ys-moto')],
			['first_registration', __('Mise en circulation', 'ys-moto')],
			['marque', 'marque'],
			['moto_type', 'catégorie']
		];
		const resp = [];
		for (let [value, label] of choices) {
			if (attributes.visible_metas.includes(value)) {
				resp.push({label, value, is_checked: true});
			} else {
				resp.push({label, value, is_checked: false});
			}
		}
		return resp;
	}


  return (
    <InspectorControls>
      <PanelBody title='Présentation'>
          <SelectControl
            label={__("Affichage", 'ys-moto')}
            value={ attributes.presentation }
            options={ [
                { label: __('En grille', 'ys-moto'), value: 'grid' },
                { label: __('En tableau', 'ys-moto'), value: 'table' },
            ] }
            onChange={ ( presentation ) => setAttributes( {presentation} ) }
            __nextHasNoMarginBottom
          />
        {
          (attributes.presentation === 'grid') &&
              <RangeControl 
                label={__("colonnes", 'ys-moto')}
                value={ attributes.columns }
                onChange={ ( columns ) => setAttributes( {columns} ) }
                min={ 1 }
                max={ 6 }
              />
        }
      </PanelBody>
      <PanelBody title={__("Données à afficher", 'ys-moto')}>
          <MultipleCheckBoxControl choices={getChoices()} editChoices={editChoices}/>
      </PanelBody>
    </InspectorControls>
  )
}