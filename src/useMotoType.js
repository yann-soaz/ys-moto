import { useEntityProp } from "@wordpress/core-data";
const { useSelect } = wp.data;

const EMPTY_ARRAY = [];
export default function useMotoMarque (postId) {
  const [types] = useEntityProp('postType', 'moto', 'moto_type', postId);
  if (types == undefined || !types.length)
    return EMPTY_ARRAY;
  const types_list = useSelect(select => 
    select('core').getEntityRecords( 'taxonomy', 'moto_type', {include: types} )
  )
  return types_list;
}