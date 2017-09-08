<section data-template-name="contacts" class="js-router-template content container contacts" id="content">
    <div class="inner">
        <h1>{$_object|escape}</h1>
    </div>

    <div class="short">{$shortText}</div>
    <div class="formWrap">
        <div class="formWrap__text">{$beforeFormText}</div>

        {if $smarty.get.sent == 'ok'}

            <div class="message ok formWrap__text">
                {$textAfterSubmit}
            </div>

        {else}

        <form action="{request_url}" method="post">
            <input type="hidden" name="returnOk" value="{request_url add="sent=ok"}"/>

            <div class="oneLine">
                <div class="field">
                    <label for="name">{alias code="name"}*</label>
                    <input type="text" name="name" id="name" />
                    <label for="name" class="hasError"></label>
                </div>
            </div>

            <div class="oneLine">
                <div class="field">
                    <label for="subject">{alias code="subject"}*</label>
                    <input type="text" name="subject" id="subject" />
                    <label for="subject" class="hasError"></label>
                </div>


                <div class="field">
                    <label for="company">{alias code="company"}*</label>
                    <input type="text" name="company" id="company" />
                    <label for="company" class="hasError"></label>
                </div>
            </div>

            <div class="oneLine">

                <div class="field">
                    <label for="phone">{alias code="phone"}</label>
                    <input type="tel" name="phone" id="phone" />
                    <label for="phone" class="hasError"></label>
                </div>

                <div class="field">
                    <label for="email">{alias code="email"}*</label>
                    <input type="email" name="email" id="email" />
                    <label for="email" class="hasError"></label>
                </div>

            </div>

            <div class="oneLine">
                <div class="field full">
                    <label for="text">{alias code="text"}*</label>
                    <textarea name="text" id="text" cols="30" rows="10"></textarea>
                    <label for="text" class="hasError"></label>
                </div>
            </div>

            <div class="oneLine">
                <button type="submit">{alias code=submit}</button>
            </div>

        </form>
        {/if}


    </div>




</section>