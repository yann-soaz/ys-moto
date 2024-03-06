/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import { useEntityProp } from '@wordpress/core-data';
import useMotoMarque from '../useMotoMarque';
import useMotoType from '../useMotoType';
import MotorCycleMetaBlockOption from './MotorCycleMetaBlockOptions';
import GridDatas from './GridDatas';
import TableDatas from './TableDatas';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
const METAS = [
	['model', __('Modèle', 'ys-moto')],
	['engine_size', __('Cylindrée', 'ys-moto')],
	['mileage', __('Kilométrage', 'ys-moto')],
	['first_registration', __('Mise en circulation', 'ys-moto')]
];
export default function Edit(	{attributes,setAttributes, context} ) {
  const post_type = context.postType;

	const marques = useMotoMarque(context.postId);
	const moto_types = useMotoType(context.postId);
	
	const [metas, setMetas] = useEntityProp( 'postType', post_type, 'meta', context.postId);
	function buildDatas () {
		const datas = [];
		if (attributes.visible_metas.includes('marque')) {
			if (!!marques && marques.length) {
				let m = '';
				for (let i = 0; i < marques.length; i++) {
					m += marques[i].name+((i+1 === marques.length) ? '' : ', ');
				}
				datas.push([__('Marque', 'ys-moto'), m]);
			} else {
				datas.push([__('Marque', 'ys-moto'), '-']);
			}
		}
		if (attributes.visible_metas.includes('moto_type')) {
			if (!!moto_types && moto_types.length) {
				let mt = '';
				for (let i = 0; i < moto_types.length; i++) {
					mt += moto_types[i].name+((i+1 === moto_types.length) ? '' : ', ');
				}
				datas.push([__('Catégorie', 'ys-moto'), mt]);
			} else {
				datas.push([__('Catégorie', 'ys-moto'), '-']);
			}
		}
		let m_keys = (metas != undefined) ? Object.keys(metas) : [];
		for (let [meta_name, meta_label] of METAS) {
			if (attributes.visible_metas.includes(meta_name)) {
				datas.push([meta_label, (m_keys.includes(meta_name)) ? metas[meta_name] : '-']);
			}
		}
		return datas;
	}

	return (
		<>
			<MotorCycleMetaBlockOption {...{attributes, setAttributes}}/>
			<div { ...useBlockProps({
				className: (attributes.presentation === 'grid') ? `moto-data-grid-display moto-grid-${attributes.columns}` : 'moto-data-table-display wp-block-table'
			}) }>
				{
					(attributes.presentation === 'grid') ?
						<GridDatas datas={buildDatas()}/>
					:
						<TableDatas datas={buildDatas()}/>
				}
			</div>
		</>
	);
}
