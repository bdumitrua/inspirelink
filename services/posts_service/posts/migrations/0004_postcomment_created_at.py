# Generated by Django 5.0.4 on 2024-05-08 23:00

import django.utils.timezone
from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('posts', '0003_postcomment_postcommentlike'),
    ]

    operations = [
        migrations.AddField(
            model_name='postcomment',
            name='created_at',
            field=models.DateTimeField(default=django.utils.timezone.now),
        ),
    ]