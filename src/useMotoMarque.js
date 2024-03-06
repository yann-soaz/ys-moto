import { useEntityProp } from "@wordpress/core-data";
const { useSelect } = wp.data;

const EMPTY_ARRAY = [];
export default function useMotoMarque (postId) {
  const [marques] = useEntityProp('postType', 'moto', 'marque', postId);
  if (marques == undefined || !marques.length)
    return EMPTY_ARRAY;
  const marques_list = useSelect(select => 
    select('core').getEntityRecords( 'taxonomy', 'marque', {include: marques} )
  )
  return marques_list;
}