export const DataRankings = data => {
  // Sort the data by total score in descending order
  data.value.sort((a, b) => b.total - a.total)

  // Assign ranks, ensuring that items with the same total score get the same rank
  let rank = 1
  data.value.forEach((item, index) => {
    if (index > 0 && item.total === data.value[index - 1].total) {
      item.rank = data.value[index - 1].rank
    } else {
      item.rank = rank
    }
    rank++
  })

  return data
}

export const Result = total => {
  if (total >= 50) return 'Pass'
  return 'Fail'
}

export const Grade = score => {
  if (score >= 90) return 'A'
  if (score >= 80) return 'B'
  if (score >= 70) return 'C'
  if (score >= 60) return 'D'
  if (score >= 50) return 'E'
  return 'F'
}
export const GradePlus = score => {
  if (score >= 98) return 'A+'
  if (score >= 90) return 'A'
  if (score >= 85) return 'B+'
  if (score >= 80) return 'B'
  if (score >= 75) return 'C+'
  if (score >= 70) return 'C'
  if (score >= 65) return 'D'
  if (score >= 60) return 'E'
  return 'F'
}
