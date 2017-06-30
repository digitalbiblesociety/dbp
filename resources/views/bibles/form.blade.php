<ul class="accordion" data-accordion  data-allow-all-closed="true">
    <li class="accordion-item is-active" data-accordion-item>
        <a href="#" class="accordion-title">Titles</a>
        <div class="accordion-content" data-tab-content>
            <div class="row">
                <table>
                    <thead>
                    <tr>
                        <td>Glotto ID</td>
                        <td>Title</td>
                        <td>Vernacular</td>
                        <td>Clone</td>
                        <td>Remove</td>
                    </tr>
                    </thead>
                    <tbody class="title-group">
                    <tr id="clonedTitleInput1" class="clonedInput" data-type="title">
                        <td><label for="glotto" class=""><input type="text" name="glotto_id[]" placeholder="Glotto ID"></label></td>
                        <td><label for="title" class=""><input type="text" name="title[]" placeholder="Title"></label></td>
                        <td><label for="vernacular"><input type="radio" name="vernacular[]" placeholder="Vernacular"></label></td>
                        <td><div class="button clone">+</div></td>
                        <td><div class="button remove">-</div></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </li>
    <li class="accordion-item" data-accordion-item>
        <a href="#" class="accordion-title">Organizations</a>
        <div class="accordion-content" data-tab-content>
            <div class="row">
                <table>
                    <thead>
                    <tr>
                        <td>Organization</td>
                        <td>Contribution Type</td>
                        <td>Clone</td>
                        <td>Remove</td>
                    </tr>
                    </thead>
                    <tbody class="organization-group">
                    <tr id="clonedOrganizationInput1" class="clonedInput" data-type="organization">
                        <td>
                            <select name="organization_id">
                                <option></option>
                            </select>
                        </td>
                        <td>
                            <select name="contribution_type">
                                <option>Publisher</option>
                            </select>
                        </td>
                        <td><div class="button clone">+</div></td>
                        <td><div class="button remove">-</div></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </li>
    <li class="accordion-item" data-accordion-item>
        <a href="#" class="accordion-title">MetaData</a>
        <div class="accordion-content" data-tab-content>
            <div class="row">
                <div class="small-3 columns"><label>Date Published<input type="date" placeholder="date"></label></div>
                <div class="small-3 columns"><label>Script
                        <select name="script">
                            <option>Latin</option>
                        </select>
                    </label></div>
                <div class="small-3 columns"><label>Scope
                        <select name="portions">
                            <option>Full Bible with Apocrypha</option>
                            <option>Full Bible</option>
                            <option>New Testament and Portions</option>
                            <option>Portions</option>
                        </select>
                    </label></div>
                <div class="small-3 columns"><label>Scope
                        <select name="copyright">
                            <option></option>
                        </select>
                    </label></div>
            </div>
        </div>
    </li>
</ul>

