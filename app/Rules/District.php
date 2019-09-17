<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class District implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    /** @phan-suppress-next-line PhanUnusedPublicMethodParameter */
    public function passes($attribute, $value)
    {
        $districts = [
            "Kalangala", "Buikwe", "Buvuma", "Namayingo", "Katakwi", "Nakapiripirit", "Kamwenge", "Mbarara", "Kotido", "Agago", "Bulambuli", "Kween", "Amudat", "Kaberamaido", "Amolatar", "Kaliro", "Namutumba", "Kitgum", "Lamwo", "Pader", "Sironko", "Mbale", "Bugiri", "Busia", "Butaleja", "Mayuge", "Manafwa", "Tororo", "Masaka", "Kasese", "Ntungamo", "Bushenyi", "Rukungiri", "Ibanda", "Mbararaa", "Kabale", "Kanungu", "Nebbi", "Zombo", "Ngora", "Bukedea", "Budaka", "Kibuku", "Pallisa", "Serere", "Kalungu", "Gomba", "Amuru", "Amuria", "Otuke", "Oyam", "Kiryandongo", "Kibale", "Ntoroko", "Kyegegwa", "Napak", "Moroto", "Bukwa", "Bukomansimbi", "Lwengo", "Lyantonde", "Butambala", "Rubirizi", "Sheema", "Mitooma", "Buhweju", "Bududa", "Jinja", "Kayunga", "Iganga", "Alebtong", "Soroti", "Buyende", "Kumi", "Mpigi", "Adjumani", "Yumbe", "Kampala", "Mukono", "Wakiso", "Sembabule", "Mityana", "Nakaseke", "Dokolo", "Lira", "Gulu", "Nwoya", "Masindi", "Apac", "Buliisa", "Hoima", "Kabarole", "Kapchorwa", "Kaabong", "Abim", "Rakai", "Isingiro", "Kisoro", "Luuka", "Kamuli", "Arua", "Koboko", "Moyo", "Luweero", "Mubende", "Nakasongola", "Bundibugyo", "Kyankwanzi", "Kole", "Maracha", "Kiboga", "Kyenjojo",
        ];
        return in_array($value, $districts, false);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid district';
    }
}
