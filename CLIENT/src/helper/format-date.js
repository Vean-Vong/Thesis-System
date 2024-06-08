import moment from "moment";

export const formatDDMMyyyy = (date) => {
  return moment(date).format("DD-MM-yyyy");
};
