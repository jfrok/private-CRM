<div id="filterModal" class="modal">
    <div class="modal-content">
        <div class="col s1 m4">
            Filteren op status:
            <select id="boardsStatus" name="status">
                <option value="Open">Open</option>
                <option value="Afgerond">Afgerond</option>
                <option value="Alle statussen">Alle statussen</option>
            </select>
        </div>
    </div>
</div>

<div id="createBoardModal" class="modal">
    <div class="modal-content">

        <form id="createBoardForm">
            @csrf

            <div>
                <label>Titel</label>
                <input type="text" name="title" id="boardTitle" required>
            </div>

        </form>

        <button onclick="submitBoard()"
                class="btn waves-effect waves-dark bg-light black-text">
            <b>Maak board aan</b>
        </button>
    </div>
</div>

<div id="editBoardModal" class="modal">
    <div class="modal-content">

        <form id="editBoardForm">
            @csrf

            <div class="row">
                <label>Titel</label>
                <input type="text" name="title" id="boardEditTitle" value="" required>
            </div>

            <div class="row">
                <div>
                    <label>Status</label>
                    <select id="boardEditStatus" class="browser-default" name="status">
                        <option value="Open">Open</option>
                        <option value="Afgerond">Afgerond</option>
                    </select>
                </div>
            </div>

            <input type="hidden" id="boardEditId" name="id">

        </form>

        <button onclick="editBoard()"
                class="btn waves-effect waves-dark bg-light black-text">
            <b>Pas board aan</b>
        </button>
    </div>
</div>
