<!DOCTYPE html>
<html lang="ru">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous">

  <title>Телефонный справочник</title>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand">Телефонный справочник</a>
  </div>
</nav>

<div class="container">
  <div class="row bg-white shadow-sm rounded my-4 p-4">
    <div class="col">
      <div class="row">
        <div class="col">
          <div class="table-responsive-sm">
            <table class="table table-bordered text-center">
              <thead>
              <tr>
                <th scope="col">ФИО</th>
                <th scope="col">Телефон</th>
                <th scope="col">Кем приходится</th>
                <th scope="col">Кнопки действий</th>
              </tr>
              </thead>
              <tbody id="mainTBody">
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col text-center">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                  data-bs-target="#addModal">
            Добавить
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1"
     aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Добавить</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addForm">
          <div class="mb-3">
            <label for="FIOInput" class="form-label">
              ФИО
            </label>
            <input type="text" class="form-control" id="FIOInput"
                   name="FIOInput">
          </div>
          <div class="mb-3">
            <label for="phoneInput" class="form-label">
              Телефон
            </label>
            <input type="text" class="form-control" id="phoneInput"
                   name="phoneInput">
          </div>
          <div class="mb-3">
            <label for="whoHasToInput" class="form-label">
              Кем приходится
            </label>
            <input type="text" class="form-control" id="whoHasToInput"
                   name="whoHasToInput">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Закрыть
        </button>
        <button type="button" class="btn btn-primary"
                id="addButton">
          Добавить
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Пользовательский JavaScript -->
<script>
  /**
   * Блок пользовательских функций
   */

  /**
   * Отправка данных
   */
  async function sendData(url = '', data = {}) {
    const response = await fetch(url, {
      method: 'POST',
      body: data
    });
    return await response.json();
  }

  document.addEventListener('DOMContentLoaded', () => {

    const getElements = () => {
      MAIN_TBODY.innerHTML = '';
      sendData('getElements.php')
        .then((data) => {
          data.map((el) => {
            const TR = document.createElement('tr');
            const TH = document.createElement('th');
            TH.setAttribute('scope', 'row');
            TH.innerText = el.FIO;
            TR.appendChild(TH);
            const TD_phone = document.createElement('td');
            TD_phone.innerText = el.phone;
            TR.appendChild(TD_phone);
            const TD_who_has_to = document.createElement('td');
            TD_who_has_to.innerText = el.who_has_to;
            TR.appendChild(TD_who_has_to);
            const TD_buttons = document.createElement('td');
            TD_buttons.insertAdjacentHTML('beforeend',
              `
                <div class="row">
                  <div class="col">
                    <button type="button" class="btn btn-danger btn-sm
                    deleteButton"
                      data-element_id="${el.id}">
                      Удалить
                    </button>
                  </div>
                </div>
                <div class="row pt-2">
                  <div class="col">
                    <button type="button" class="btn btn-secondary btn-sm
                    editButton"
                      data-fio="${el.FIO}" data-phone="${el.phone}"
                            data-who_has_to="${el.who_has_to}"
                            data-element_id="${el.id}">
                      Редактировать
                    </button>
                  </div>
                </div>
            `
            );
            TR.appendChild(TD_buttons);
            MAIN_TBODY.appendChild(TR);
          });
          this.EDIT_BUTTONS = document.querySelectorAll('.editButton');
          this.EDIT_BUTTONS.forEach((el) => {
            el.addEventListener('click', editElement)
          })
          this.DELETE_BUTTONS = document.querySelectorAll('.deleteButton');
          this.DELETE_BUTTONS.forEach((el) => {
            el.addEventListener('click', deleteElement)
          })
        });
    }

    /**
     * Добавить элемент
     */
    const addElement = async () => {
      const ADD_FORM = document.querySelector('#addForm');
      const FORM_DATA = new FormData(ADD_FORM);
      await sendData('addElement.php', FORM_DATA)
        .then((data) => {
          ADD_MODAL.toggle();
        });
      getElements();
    };

    /**
     * Изменить элемент
     */
    const editElement = async (event) => {
      const FIO = event.target.dataset.fio;
      const PHONE = event.target.dataset.phone;
      const WHO_HAS_TO = event.target.dataset.who_has_to;
      await document.body.insertAdjacentHTML(
        'beforeend',
        `
        <div class="modal fade" id="editModal" tabindex="-1"
          aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Редактировать</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="editForm">
                  <div class="mb-3">
                    <label for="FIOInput" class="form-label">
                      ФИО
                    </label>
                    <input type="text" class="form-control" id="FIOInput"
                           name="FIOInput" value="${FIO}">
                  </div>
                  <div class="mb-3">
                    <label for="phoneInput" class="form-label">
                      Телефон
                    </label>
                    <input type="text" class="form-control" id="phoneInput"
                           name="phoneInput" value="${PHONE}">
                  </div>
                  <div class="mb-3">
                    <label for="whoHasToInput" class="form-label">
                      Кем приходится
                    </label>
                    <input type="text" class="form-control" id="whoHasToInput"
                           name="whoHasToInput" value="${WHO_HAS_TO}">
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  Закрыть
                </button>
                <button type="button" class="btn btn-primary"
                        id="editButton">
                  Изменить
                </button>
              </div>
            </div>
          </div>
        </div>
        `
      );
      const EDIT_MODAL = new bootstrap.Modal('#editModal', {});
      const EDIT_MODAL_DOM = document.querySelector('#editModal');
      const EDIT_BUTTON = document.querySelector('#editButton');

      EDIT_MODAL.toggle();
      EDIT_MODAL_DOM.addEventListener('hidden.bs.modal', () => {
        EDIT_MODAL_DOM.remove();
      });
      EDIT_BUTTON.addEventListener('click', () => {
        const EDIT_FORM = document.querySelector('#editForm');
        const EDIT_FORM_DATA = new FormData(EDIT_FORM);

        EDIT_FORM_DATA.append('ELEMENT_ID', event.target.dataset.element_id);

        sendData('editElement.php', EDIT_FORM_DATA)
          .then((data) => {
            getElements();
          });
        EDIT_MODAL.toggle();
      });
    };

    /**
     * Удалить элемент
     */
    const deleteElement = async (event) => {
      const ELEMENT_ID = event.target.dataset.element_id;
      const FORM_DATA = new FormData();
      FORM_DATA.append('ELEMENT_ID', ELEMENT_ID);
      await sendData('deleteElement.php', FORM_DATA)
        .then((data) => {
          console.log(data);
        });
      getElements();
    };

    /**
     * Блок определения констант
     */
    const ADD_BUTTON = document.querySelector('#addButton');
    const MAIN_TBODY = document.querySelector('#mainTBody');
    const ADD_MODAL = new bootstrap.Modal('#addModal', {});

    getElements();

    /**
     * Блок добавления слушателя событий
     */
    ADD_BUTTON.addEventListener('click', addElement);
  });
</script>

<!-- Optional JavaScript; choose one of the two! -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
        crossorigin="anonymous"></script>

</body>
</html>