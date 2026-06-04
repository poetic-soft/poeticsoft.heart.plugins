const { apiFetch } = wp

export const getPageStatus = (pageId) => {

  return apiFetch({
    path: `poeticsoft/heart/campus/v1/page/access/get/${pageId}`,
    method: "GET"
  })
  .catch(error => console.error('Heart Campus API Error:', error));
}