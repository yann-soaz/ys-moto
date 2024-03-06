import { useState } from "@wordpress/element";
import { PanelBody, PanelRow, CheckboxControl } from '@wordpress/components';

function CustomCheckBoxControl ({value, label, editChoices, is_checked}) {
  return (
    <CheckboxControl
        label={label}
        checked={ is_checked }
        onChange={ checked => {
            editChoices(value, checked);
        } }
    />
  );
}

export default function MultipleCheckBoxControl ({choices, editChoices}) {
  return (
    <>
      {
        choices.map((choice) => 
            <PanelRow>
              <CustomCheckBoxControl {...choice} editChoices={editChoices}/>
            </PanelRow>
          )
      }
    </>
  );
}


