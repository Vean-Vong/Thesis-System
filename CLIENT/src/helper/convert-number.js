export function latinNumToKhmerNum(n) {
  if (!n) return

  n = n.toString()

  n = n.replace(/0/g, '០')
  n = n.replace(/1/g, '១')
  n = n.replace(/2/g, '២')
  n = n.replace(/3/g, '៣')
  n = n.replace(/4/g, '៤')
  n = n.replace(/5/g, '៥')
  n = n.replace(/6/g, '៦')
  n = n.replace(/7/g, '៧')
  n = n.replace(/8/g, '៨')
  n = n.replace(/9/g, '៩')

  return n
}

export function removeLatinNumFromString(s) {
  if (!s) return

  s = s.replace(/0/g, '០')
  s = s.replace(/1/g, '១')
  s = s.replace(/2/g, '២')
  s = s.replace(/3/g, '៣')
  s = s.replace(/4/g, '៤')
  s = s.replace(/5/g, '៥')
  s = s.replace(/6/g, '៦')
  s = s.replace(/7/g, '៧')
  s = s.replace(/8/g, '៨')
  s = s.replace(/9/g, '៩')

  return s
}
