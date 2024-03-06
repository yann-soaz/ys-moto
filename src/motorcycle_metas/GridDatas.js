import { __experimentalHeading as Heading } from '@wordpress/components';


export default function GridDatas ({datas}) {
  return (
    <>
      {
        datas.map(([label, info], index) =>         
          <div className="moto-data-item">
            <Heading level={5}>
              {label}
            </Heading>
            <span>
              {info}
            </span>
          </div>
        )
      }
    </>
  )
}