import {CircularProgressbar, buildStyles} from 'react-circular-progressbar'
import 'react-circular-progressbar/dist/styles.css'

type Props = {
    percentageUsed: number
}
export default function ProgressBar({percentageUsed}: Props) {
 
  return (
    <CircularProgressbar
        value={percentageUsed}
        styles={buildStyles({
            pathColor:'var(--progress-path-color)',
            trailColor:'var(--progress-trail-color)',
            textSize:8,
            textColor: 'var(--progress-text-color)'
        })}
        text={`${percentageUsed}% Gastado`}
    />
  )
}
