import moment from "moment";

export const format = (date, formate) => {
  return moment(date).format(formate);
};
