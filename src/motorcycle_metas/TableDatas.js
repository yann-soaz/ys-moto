import { __experimentalHeading as Heading } from '@wordpress/components';

export default function TableDatas ({datas}) {
  return (
    <table>
      <tbody>
      {
        datas.map(([label, info], index) =>         
          <tr key="index">
            <th className="moto-data-item">
              <Heading level={5}>
                {label}
              </Heading>
            </th>
            <td className="moto-data-item">
              <span>
                {info}
              </span>
            </td>
          </tr>
        )
      }
      </tbody>
    </table>
  )
}